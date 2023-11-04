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

