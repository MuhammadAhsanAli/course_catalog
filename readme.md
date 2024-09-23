# Course Catalog

## Overview

This project implements a simple course catalog RESTful API and a frontend to display courses and categories. The API is built using pure PHP, following PSR-12 standards, and focuses on best coding practices. The Single Page Application (SPA) frontend allows users to view all courses, filter by categories, and see courses count by category.

## Features

1. **RESTful API**:
   - Built according to the provided Swagger specification.
   - API provides endpoints for retrieving categories and courses.

2. **Database**:
   - Migrations and seeding located in `/database/migrations`.

3. **Single Page Application (SPA)**:
   - Displays all courses by default.
   - Filters courses by category on click.
   - Course titles and descriptions are truncated with ellipses on desktop layouts.
   - Shows the count of courses (including subcategories) within each category.
   - Each course card displays the main category name.

## Project Structure

- **API Endpoints**:
   - `/categories`: Fetch all categories.
   - `/categories/{id}`: Fetch a specific category by ID.
   - `/courses`: Fetch all courses (optionally filtered by category).
   - `/courses/{id}`: Fetch a specific course by ID.

- **Database Migrations & Seeding**:
   - Located in the `/database/migrations` folder.

- **Frontend**:
   - The frontend is a SPA built with pure HTML, CSS, and Bootstrap.
   - Layout files are in the `/design` folder.

## Setup Instructions

### 1. Clone the Repository

```bash
git clone `https://github.com/MuhammadAhsanAli/course_catalog.git`
cd course-catalog
```
### 2. Build and Run with Docker
```
docker-compose up --build
```

## Hosts:
API host: http://api.cc.localhost:8083

DB host: http://db.cc.localhost

Front host: http://cc.localhost:8083

Traefik dashboard: http://127.0.0.1:8082/dashboard/#/

DB credentials - look at the docker-compose.yml

Api docs are in swagger.yml
