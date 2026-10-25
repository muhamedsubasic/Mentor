# AI usage log

> Required by the project rules: one section per milestone with the AI tools used, the most
> important prompts, and **at least two cases where the AI was wrong** – what was wrong,
> how it was noticed, and how it was fixed. If you do not use AI, write that here.

## Milestone 1 – Setup, static frontend, ERD

**Tools used:** Deepseek (static HTML views, CSS layout), reviewed and adjusted by hand.

**Most important prompts**
- "Create User and Admin pages following layout of the template, reuse templates CSS where possible. Admin page should contain sidebar with dashboard, users list, courses and purchases. User sidebar should contain Overview, My Courses and Subscription. Following SPApp from app.js for navigation"
- "Implement a fix to app.js where SPApp will not break main.js animations from the template"

**Cases where the AI was wrong**
1. *What:* The sidebar was not following SPApp navigation properly.

   *How noticed:* There was page refreshes.

   *Fix:* In app.js implemented when to change the views, when the sidebar should be shown and when not. Added back Hiding sections which Ai removed.

2. *What:* Animations were not played

   *How noticed:* Animations were not played for each page.

   *Fix:* .