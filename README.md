## Objectives of the Project 

- Upgrade an existing Laravel backend application to Laravel 10.

- Implement an Article Revision Feature that automatically saves the previous state of an article every time it is updated.

## Branch 1 - `main` branch

- Upgrade Laravel: Update the backend project to Laravel 10 and ensure all functionality works as expected.

- Database Migration: Create a new article_revisions table to store revision history.

- Revision Creation Logic: Use model events or observers to automatically create a revision record whenever an article is updated.

**API Endpoints:**

- GET /api/articles/{id}/revisions: Fetch the revision history for a specific article.

- GET /api/articles/{id}/revisions/{revision_id}: Fetch the data for a specific revision.

- POST /api/articles/{id}/revisions/{revision_id}/revert: Revert an article to a specific revision (authorized users only).

## Branch 2 - `feat/blade_front` branch
Added blade templates (for trial) - to replace the external react project - to make it accessible and easy to test
