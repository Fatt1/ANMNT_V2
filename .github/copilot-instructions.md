# Copilot Workspace Instructions

## Project Context
- Laravel 11 e-commerce training project.
- Prioritize maintainable Laravel conventions.
- Keep code and naming clear and explicit.

## Engineering Rules
- Use resource controllers and Blade views for admin CRUD.
- Keep routes named and grouped by domain.
- Use Tailwind utility classes for UI consistency.
- Show user-friendly messages only on the UI; avoid exposing stack traces.

## Data Rules
- Product entity should include: name, slug, description, image_url, price, stock, is_active.
- Prefer pagination on admin and listing pages.

## Delivery Rules
- For any new feature, include: migration changes, controller actions, routes, and Blade screens.
- Keep generated code ready to run with php artisan migrate.
