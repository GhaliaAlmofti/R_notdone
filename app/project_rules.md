# Project Rules (MUST FOLLOW)

- Laravel project with role-based structure.
- Do NOT move files outside:
  app/Http/Controllers/{Auth,Parent,Admin,SchoolAdmin}
  routes/{web,auth,parent,admin,school_admin}.php
  resources/views/{auth,parent,admin,school_admin,layouts}
- All user-visible strings MUST use __('...') for Arabic/English later.
- Keep changes minimal: only edit files explicitly mentioned.
- If a route name is referenced, ensure it exists in route:list.
- If adding new columns, add migration + update $fillable + casts.
- Any feature must include:
  Controller + Route + View (if UI) + clear status flow.
