class Tracking:

    def __init__(self, person_id, centroid):

        self.person_id = person_id
        self.centroids = [centroid]
        self.is_counted = False
        self.initial_movement = None
