---
agent: ask
model: GPT-5.3-Codex
description: "Use when implementing a Laravel e-commerce feature end-to-end (migration, routes, controller, Blade, Tailwind UI)."
---
Implement a Laravel 11 e-commerce feature with the following requirements:

Feature name: ${input:feature_name}
Business goal: ${input:business_goal}
Data fields: ${input:data_fields}
Routes needed: ${input:routes}
UI screens needed: ${input:ui_screens}

Constraints:
1. Follow Laravel conventions and clear naming.
2. Include migration updates if schema changes are needed.
3. Implement controller logic and route definitions.
4. Create realistic Blade pages with Tailwind CSS.
5. Keep UI error messages generic and user-friendly.
6. At the end, summarize modified files and run instructions.
