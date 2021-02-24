# appointmentsPluginWP
This project is a plugin I've made for WordPress using <i>PHP</i>, <i>jQuery</i> and <i>Ajax</i>.<br />
I've made it only with Bootstrap, trying to focus only on functionalities.

The public front-end is represented by a form, which the clients are complete, selecting only the dates are available.
![First-page](https://user-images.githubusercontent.com/73690608/108724748-59c59b00-752e-11eb-9b85-142b8b24dd42.png)
<br />
<br />
In the admin page, on the main menu, we can see all the existing appointments in a calendar(made with <i>fullCalendar.io</i>). The appointments can be marked with a color: blue or green. <br />
The blue color means every appointment made by a client which is not confirmed yet. (The confirmation is made manually by the user of this plugin). <br />
The green color means the confirmed appointments. <br />
There are only 3 days of the week and different hours for each day because this plugin was made customized for a person. These settings can be easily changed.
![calendar-view](https://user-images.githubusercontent.com/73690608/108725542-2b948b00-752f-11eb-983f-31d930ae066e.png)
<br />
<br />
We can add a new appointment from the admin menu by pressing the bottom button "Adauga programare". We can create a new appointment for a new person or we can select an existing one(made with <i>select2.js</i>). This appointment will be marked automatically with the color green.
![adding-appointment-backend](https://user-images.githubusercontent.com/73690608/108726832-88447580-7530-11eb-8a5e-c80f78e2e0ab.png)
<br />
<br />
By clicking on an existing event, we can confirm it by pressing the button "Programeaza" or we can change the datetime of it or we can send the client 3 datetimes for rescheduling.
![confirming-or-reschedule](https://user-images.githubusercontent.com/73690608/108727281-14569d00-7531-11eb-8ed6-14671ec31f43.png)
<br />
<br />
In the submenu "Clienti", we can see all the existing clients in the database (I've made an unique client based on his phone number).<br />
On this page we can see all the appointments for specified client or to edit informations about client.
![clients-view](https://user-images.githubusercontent.com/73690608/108728102-eaea4100-7531-11eb-991c-4b202a559423.png)
![appointments-per-client](https://user-images.githubusercontent.com/73690608/108728123-f178b880-7531-11eb-9845-96a35f07a307.png)
![edit-client-info](https://user-images.githubusercontent.com/73690608/108728128-f2114f00-7531-11eb-889d-0bd34fe08b16.png)
<br />
<br />
In the submenu "Date dezactivate", we can disabled a date to cannot be selected from a client by creating a new appointment. (E.g. holydays)
![deactivate-date](https://user-images.githubusercontent.com/73690608/108728657-7794ff00-7532-11eb-8ad1-e2e94c6ed15e.png)



