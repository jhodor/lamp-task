# Coding Challenge

Your task is to develop a web-based application that loads a list of addresses with latitude/longitude coordinates
from a file (data.csv provided) and presents the data on an interactive grid. When a location is selected,
the application should dynamically display the five nearest locations, based on their distance, in a user-friendly way.

## Requirements:

### Backend:

- Utilize the LAMP (Linux, Apache, MySQL, PHP) stack for this challenge.
- Create a MySQL database and import the provided CSV data into a suitable table.


### Frontend:
- Design a simple user interface that displays the list of addresses in a grid format.
- Implement a dropdown or search functionality that allows users to choose a location from the list.
- Bonus: display the selected location's coordinates on a map using a map library or service (e.g., Google Maps API).

### Functionality:
- When a location is chosen, use the Haversine formula to calculate the distance between the chosen location and all
  other locations in the database.
- Present the five nearest locations in a separate section of the user interface, showing their distances and names.

### Additional Guidelines:
- Use PHP to interact with the database and retrieve data.
- Utilize JavaScript to enable interactivity and dynamic updates.
- Feel free to use any open-source libraries or frameworks you're comfortable with.
- Demonstrate your depth of knowledge (show us that you're a senior software engineer).
- Your solution should be submitted as a GitHub repository with clear instructions on how to run the application.

## Why This Challenge:

This challenge allows applicants to demonstrate their ability to work with real-world data, interact with both
the backend and frontend, and create a user-friendly interface. It's aligned with our company's focus on logistics
and gives candidates a practical task that reflects the type of work they would be involved in. The interactive
nature of the challenge adds an engaging aspect that showcases their skills beyond coding logic.

# Solution

Since I work on MacBook, I prepared two variants of the LAMP configuration, one using Vagrant and Virtualbox and
the other using Docker. Either of them can be used to start this application.

The frontend of the application uses the following open source libraries:
- jQuery 3.7.1 (https://jquery.com/)
- DataTables 1.13.6 (https://datatables.net/) for tabular data visualization. It is a plugin for the jQuery.
- Leaflet 1.9.4 (https://leafletjs.com) to display an interactive map.

The backend, written in PHP, is very simple and does not use any external libraries. Calls to the MySQL database
are made using the PDO-MySQL PHP extension, and the common code to read the configuration from the file and connect
to the database is separated into a library file included in the other files.

The database configuration is stored in an `web/.env` configuration file. 

## Deployment

### Using Vagrant and Virtualbox

Once you have Virtualbox and Vagrant installed, run this command:
```
cd vagrant
vagrant up
```

Then open the application use http://localhost:8080/ and upload `data.csv` file.

When you are done, use command `vagrant destroy` to stop and delete LAMP virtual machine and 
`vagrant box remove debian/bookworm64` to remove downloaded box.

### Using Docker

Once you have a Docker installed, run this command:
```
cd docker
docker-compose up
```

Then open the application use http://localhost:8080/ and upload `data.csv` file.

When you are done, just press Ctrl-c to stop Docker containers. Use command `docker rm lamp-task-web lamp-task-db`
to remove them and `docker image rm lamp-task-web` to remove builded image.

### Manual deployment on existing LAMP server

If you want to deploy this application manually on an existing LAMP configuration:
- prepare a virtual server pointing to the `./web` directory as a `DOCUMENT_ROOT`
- make sure that `web/uploads` directory is writable by your httpd process
- configure database access settings in the `web/.env` file. Note that the application will automatically create
  or truncate `locations` table in it's database when you upload the CSV file.
- open http://localhost:8080/ and upload `data.csv` with locations.

## Running the application

Once you have deployed your application, you should open a web browser with the location http://localhost:8080/ 
It will display the form to upload your CSV file of locations. During upload the file is converted into a MySQL table.

Then you select an address you are interested in using the paginated table. You can sort the contents by clicking
on the appropriate header, or search the contents using the search field. When you select the address, it is displayed
as a pin with a label on the map.

Finally, if you press the 'Show 5 nearest locations' button, the map will automatically adjust to show the previously
selected address along with its 5 nearest neighbors. Their addresses and the distances from the selected address are
shown in the table.

## Final thoughts

The DataTable was chosen because of its flexibility and ease of use. It's a robust, production-ready, open source
solution capable of parsing millions of rows. It also has an option to do the processing server-side if that is the
business requirement. 

OpenStreetMap was chosen as the map provider because, unlike Google Maps, it does not require an API key, making
it easier to deploy.

It was possible to use `composer` to include `Symfony` or `Laravel` libraries in the backend. They allow using ORM
to access the data objects and to parse the configuration file, but for a single object type it would probably be
an overkill solution (KISS principle) so I decided to use plain PDO calls and make my own `.env` parser.
