def execute(capacity_id, count_id, est_id, acc_id, status):
    import eel
    import time
    import datetime
    import numpy as np
    import tensorflow as tf
    gpus = tf.config.list_physical_devices('GPU')
    if gpus:
        try:
            tf.config.set_visible_devices(gpus[0], 'GPU')
            for gpu in gpus:
                tf.config.experimental.set_memory_growth(gpu, True)
        except RuntimeError as e:
            print(e)
    try:
        import cv2 as cv
    except ImportError as e:
        eel.say1(str(e))
    try:
        import core.utils as utils
    except ImportError as e:
        print(e)
    from tensorflow.python.saved_model import tag_constants
    from core.config import cfg
    from PIL import Image
    from deep_sort import preprocessing, nn_matching
    from deep_sort.detection import Detection
    from deep_sort.tracker import Tracker
    from tools import generate_detections
    import imutils
    from trackable import Tracking
    import urllib3
    import json
    eel.say1("done")

    def update(c_id, capacity, counter, stats):
        if stats == "Normal":
            http_proto = urllib3.PoolManager()
            query_request = http_proto.request('POST', 'http://localhost/cimo_desktop/app/update_count.php', fields={
                'count_id': c_id,
                'current_count': capacity,
                'counter': counter
            })

    detector_model = 'tensorflow'

    encoder = generate_detections.create_box_encoder('../assets/mars-small128.pb', batch_size=1)

    metric = nn_matching.NearestNeighborDistanceMetric('cosine', 0.1, None)

    tracker = Tracker(metric=metric)

    config = tf.compat.v1.ConfigProto()
    config.gpu_options.allow_growth = True
    session = tf.compat.v1.Session(config=config)

    if detector_model != 'tensorflow':

        print('00PS! model might not be correctly programmed')

    else:

        asset_model = tf.saved_model.load('../checkpoints/custom-416', tags=[tag_constants.SERVING])

        inference = asset_model.signatures['serving_default']

    video_src = cv.VideoCapture(0)

    codex = cv.VideoWriter_fourcc(*'XVID')

    save_start = time.time()

    writer = cv.VideoWriter('../video_logs/dummy.mp4', codex, 20.0, (1100, 618))

    video_name_2 = str(est_id) + "vls" + str(save_start).replace('.', '') + ".mp4"

    writer_2 = cv.VideoWriter('../video_violations/' + video_name_2, codex, 20.0, (1100, 618))

    tracked_person = {}

    capacity_threshold = 0

    if capacity_threshold == 0:

        http = urllib3.PoolManager()
        query = http.request('POST', 'http://localhost/cimo_desktop/app/get_settings.php', fields={
            'cap_id': capacity_id
        })

        response = query.data

        json_data = response.decode('utf-8')

        my_dict = json.loads(json_data)

        normal = int(my_dict['normal'])

        limit = int(my_dict['limit'])

        capacity_threshold = int(my_dict['limit'])

    eel.camera_up(True)

    eel.set_nums(normal, limit)

    status_ = 'Normal'

    isvalid = 'Valid'

    violation_count = 0

    count = 0

    start = time.time()

    video_src.set(cv.CAP_PROP_BUFFERSIZE, 3)

    while status is True:

        ret, frame = video_src.read()

        ##frame = cv.flip(frame, -1)

        resized_frame = imutils.resize(frame, width=1100, height=715)

        if frame is None:

            print('The video has ended, no more frames')

        else:

            frame = cv.cvtColor(resized_frame, cv.COLOR_BGR2RGB)

            image = Image.fromarray(frame)

        date_font = cv.FONT_HERSHEY_SCRIPT_SIMPLEX

        dt = str(datetime.datetime.now())

        line_thresh = resized_frame.shape[0] / 2

        frame_size = frame.shape[:2]

        img_data = cv.resize(frame, (416, 416))

        img_data = img_data / 255

        img_data = img_data[np.newaxis, ...].astype(np.float32)

        if detector_model != 'tensorflow':

            print('00PS! model might not be correctly programmed')

        else:

            batch_data = tf.constant(img_data)

            prediction_bbox = inference(batch_data)

            for k, v in prediction_bbox.items():

                boxes = v[:, :, 0:4]

                prediction_score = v[:, :, 4:]

        boxes, scores, classes, valid_detections = tf.image.combined_non_max_suppression(
            boxes=tf.reshape(boxes, (tf.shape(boxes)[0], -1, 1, 4)),
            scores=tf.reshape(
                prediction_score, (tf.shape(prediction_score)[0], -1, tf.shape(prediction_score)[-1])),
            max_output_size_per_class=50,
            max_total_size=50,
            iou_threshold=0.3,
            score_threshold=0.65
        )

        objects = valid_detections.numpy()[0]
        bboxes = boxes.numpy()[0]
        bboxes = bboxes[0:int(objects)]
        scores = scores.numpy()[0]
        scores = scores[0:int(objects)]
        classes = classes.numpy()[0]
        classes = classes[0:int(objects)]

        original_height, original_weight, _ = frame.shape
        bboxes = utils.format_boxes(bboxes, original_height, original_weight)

        class_names = utils.read_class_names(cfg.YOLO.CLASSES)

        names = []

        delete_index = []

        for index in range(objects):

            class_index = int(classes[index])

            class_name = class_names[class_index]

            if class_name != 'person':

                delete_index.append(index)

            else:

                names.append(class_name)

        names = np.array(names)
        bboxes = np.delete(bboxes, delete_index, axis=0)
        scores = np.delete(scores, delete_index, axis=0)
        feats = encoder(frame, bboxes)

        dets = [Detection(bbox, score, class_name, feature)
                for bbox, score, class_name, feature in zip(bboxes, scores, names, feats)]

        bxs = np.array([d.tlwh for d in dets])
        scores = np.array([d.confidence for d in dets])
        classes = np.array([d.class_name for d in dets])
        indices = preprocessing.non_max_suppression(boxes=bxs, classes=classes, max_bbox_overlap=0.50, scores=scores)
        detections = [dets[index] for index in indices]

        tracker.predict()
        tracker.update(detections)

        for index in tracker.tracks:

            if not index.is_confirmed() or index.time_since_update > 1:

                continue

            bound_box = index.to_tlbr()

            person_id = index.track_id

            class_name = index.get_class()

            x1 = int(bound_box[0])
            y1 = int(bound_box[1])
            x2 = int(bound_box[2])
            y2 = int(bound_box[3])

            centroid = int((x1 + x2) / 2), int((y1 + y2) / 2)

            cv.circle(frame, centroid, 5, (255, 255, 255), -1)

            cv.rectangle(frame, (x1, y1), (x2, y2), (255, 255, 255), 1)

            tracer = tracked_person.get(person_id, None)

            if tracer is None:

                tracer = Tracking(person_id, centroid)

            else:

                y = [c[1] for c in tracer.centroids]

                y_coordinates = y[-1]

                tracer.centroids.append(centroid)

                if tracer.initial_movement is None:

                    if y_coordinates < line_thresh:

                        tracer.initial_movement = 'Going in'

                    elif y_coordinates > line_thresh:

                        tracer.initial_movement = 'Going out'

                if tracer.is_counted is False:

                    if tracer.initial_movement == 'Going in' and y_coordinates > line_thresh:

                        tracer.initial_movement = None

                        tracer.is_counted = True

                        capacity_threshold -= 1

                        count += 1

                        update(c_id=count_id, capacity=capacity_threshold, counter=count, stats=status_)

                        print(limit - capacity_threshold)

                        if (limit - capacity_threshold) < limit:
                            if status_ == "Normal":
                                eel.get_data_num(capacity_threshold, 'normal')
                        if(limit - capacity_threshold) == limit:
                            if status_ == "Normal":
                                eel.get_data_num(capacity_threshold, 'full')
                        elif (limit - capacity_threshold) > limit:
                            status_ = "Violation"

                    elif tracer.initial_movement == 'Going out' and y_coordinates < line_thresh:

                        tracer.initial_movement = None

                        tracer.is_counted = True

                        capacity_threshold += 1

                        count -= 1

                        update(c_id=count_id, capacity=capacity_threshold, counter=count, stats=status_)

                        print(limit - capacity_threshold)

                        if (limit - capacity_threshold) < limit:
                            if status_ == "Normal":
                                eel.get_data_num(capacity_threshold, 'normal')
                        elif (limit - capacity_threshold) == limit:
                            if status_ == "Normal":
                                eel.get_data_num(capacity_threshold, 'full')
                        elif (limit - capacity_threshold) > limit:
                            status_ = "Violation"

                elif tracer.is_counted is True:

                    if tracer.initial_movement == 'Going out' and y_coordinates < line_thresh:

                        tracer.initial_movement = None

                        tracer.is_counted = False

                        capacity_threshold += 1

                        count -= 1

                        update(c_id=count_id, capacity=capacity_threshold, counter=count, stats=status_)

                        print(limit - capacity_threshold)

                        if (limit - capacity_threshold) < limit:
                            if status_ == "Normal":
                                eel.get_data_num(capacity_threshold, 'normal')
                        elif (limit - capacity_threshold) == limit:
                            if status_ == "Normal":
                                eel.get_data_num(capacity_threshold, 'full')
                        elif (limit - capacity_threshold) > limit:
                            status_ = "Violation"

                    elif tracer.initial_movement == 'Going in' and y_coordinates > line_thresh:

                        tracer.initial_movement = None

                        tracer.is_counted = False

                        capacity_threshold -= 1

                        count += 1

                        update(c_id=count_id, capacity=capacity_threshold, counter=count, stats=status_)

                        print(limit - capacity_threshold)

                        if (limit - capacity_threshold) < limit:
                            if status_ == "Normal":
                                eel.get_data_num(capacity_threshold, 'normal')
                        elif (limit - capacity_threshold) == limit:
                            if status_ == "Normal":
                                eel.get_data_num(capacity_threshold, 'full')
                        elif (limit - capacity_threshold) > limit:
                            status_ = "Violation"

            tracked_person[person_id] = tracer

        if limit - capacity_threshold < 0:
            cv.putText(frame, "Total Entries: " + str(0), (10, 480), cv.FONT_HERSHEY_PLAIN, 2, (0, 255, 0),
                       1,
                       cv.LINE_AA)
        else:
            if status_ == "Violation":
                cv.putText(frame, "Total Entries: " + str(limit - capacity_threshold), (10, 480), cv.FONT_HERSHEY_PLAIN, 2, (255, 0, 0),
                           1,
                           cv.LINE_AA)
            else:
                cv.putText(frame, "Total Entries: " + str(limit - capacity_threshold), (10, 480), cv.FONT_HERSHEY_PLAIN, 2, (0, 255, 0),
                           1,
                           cv.LINE_AA)

        cv.putText(frame, "Available Entries: " + str(limit), (10, 520), cv.FONT_HERSHEY_PLAIN, 2, (0, 255, 0), 1,
                   cv.LINE_AA)

        cv.putText(frame, isvalid, (10, 560), cv.FONT_HERSHEY_PLAIN, 2, (0, 255, 0), 1, cv.LINE_AA)

        cv.putText(frame, dt, (10, 600), cv.FONT_HERSHEY_PLAIN, 1, (0, 255, 0), 1, cv.LINE_AA)

        cv.line(frame, (0, int(resized_frame.shape[0] / 2)),
                (resized_frame.shape[1], int(resized_frame.shape[0] / 2)), (255, 255, 255), 1)

        np.asarray(frame)
        output_frame = cv.cvtColor(frame, cv.COLOR_BGR2RGB)

        if time.time() - start > 50:
            start = time.time()
            video_name = str(est_id) + "lgs" + str(start).replace('.', '') + ".mp4"
            writer = cv.VideoWriter('../video_logs/' + video_name, codex, 20.0, (1100, 618))
            eel.capture(est_id, video_name)

        if status_ == "Violation":
            isvalid = 'Invalid: under violation'
            if violation_count == 0:
                eel.get_data_num(capacity_threshold, 'violation', est_id, video_name_2, acc_id)
                violation_count = 1
            eel.violation_force_Log_out()

        if status_ == "Violation" and time.time() - start > 50:
            start = time.time()
            writer_2 = cv.VideoWriter('execute/execute.mp4', codex, 20.0, (1100, 618))

        cv.imshow('CIMO camera', output_frame)

        writer.write(output_frame)
        writer_2.write(output_frame)

        if cv.waitKey(20) & 0xff == ord('d'):

            break

    cv.destroyAllWindows()
    video_src.release()
    eel.camera_up(False)
    session.close()
