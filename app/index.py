import eel
import sys
import os
import inspect
import urllib3

current = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe())))
pardir = os.path.dirname(current)
sys.path.insert(0, pardir)

import main

eel.init('C:/xampp/htdocs/cimo_desktop/')
Token = ''
Key = ''


# close
def close_callback_index(route, websockets):
    if not websockets:
        http_logout = urllib3.PoolManager()
        logout_request = http_logout.request('POST', 'http://localhost/cimo_desktop/app/force_log_out.php', fields={
            'token': Token,
            'key': Key,
            'mobile': 'false',
        })
        print('Closing websockets! ' + str(websockets))
        sys.exit()


@eel.expose
def update_token(token, key):
    global Token, Key
    Token = token
    Key = key


@eel.expose
def close_window():
    eel.update_status(0)


@eel.expose
def start_extended():
    print('start')
    eel.show('app/extended_display.html')


@eel.expose
def eel_start():
    print('start eel')


@eel.expose
def start_cam(cap_id, count_id, est_id, acc_id, status):
    main.execute(cap_id, count_id, est_id, acc_id, status)


eel.start('app/index.html', host='localhost', port=8000, size=(1100, 715), position=(0, 0),
          close_callback=close_callback_index)
