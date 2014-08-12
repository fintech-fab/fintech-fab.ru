#Actions Calculator#

###requirements###
- php >= 5.5
- laravel >= 4.1

###data###
data in: POST request

 - terminal_id
 - auth_sign
 - event_sid
 - data


###Queue###
- calculated data http response(ff-actions-calc)
- calculated data to queue(ff-actions-calc-result)
- listening ff-actions-calc-result and sending data to foreign queue