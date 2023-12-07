# Troc Mon Toit - Housing Rental Application

## Project Description

Troc Mon Toit is a housing rental application that allows users to search, filter, book, and review housing provided by a rental agency. The application offers a variety of accommodations, amenities, and services, providing a digital experience for users.

## Key Features

- **Housing Display**

  - List of available housing with images.
  - City-based search.
  - Filtering by price, housing type, amenities, and services.

- **Favorites**

  - Adding housing to favorites for logged-in users.

- **Lodging Details**

  - Nightly price.
  - Comments and ratings left by tenants.
  - Reservation availability.

- **User Space**

  - User profile with name, surname, phone number, email address.
  - History of favorite housing.
  - History of left reviews.
  - History of reservations.

- **Lodging Reservation**

  - Selection of start and end dates of the stay.
  - Automatic calculation of the total price based on the number of reserved nights.
  - Verification of lodging availability.
  - Avoidance of simultaneous reservations by two users.
  - Lodging rating after the end of the stay.

- **Administrative Panel**
  - CRUD on housing types.
  - CRUD on amenities provided.
  - CRUD on services provided.
  - CRUD on users.
  - CRUD on housing and their characteristics.
  - Search by lodging name.
  - CRUD on reviews.

## Prerequisites

- PHP (recommended version X.X.X)
- MySQL (recommended version X.X.X)
- Composer (for installing dependencies)

## Installation

1. Clone the project from the Git repository:

```bash
git clone https://github.com/your-username/troc-mon-toit.git
cd troc-mon-toit
docker-compose up -d
cd src
composer install
cd ..
docker ps #get php container id
docker exec [container id] php app/core/db/Start.php
```

2. Access the application in your browser at http://localhost:8000.

## Usage

- Access the user interface to search, filter, and book housing.
- Log in to access your user space and manage your favorites, reviews, and reservations.
- Use the administrative panel with administrator credentials to manage housing types, amenities, services, users, housing, and reviews.

## Authors

- [AlbaNagisa](https://github.com/albanagisa)
