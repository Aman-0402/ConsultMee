# ConsultME Django Backend

Frontend lives in `../consultmee-react`.

## Run locally

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS cme CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
python manage.py migrate
python manage.py createsuperuser
python manage.py runserver
```

## API roots

- `POST /api/auth/register/`
- `POST /api/auth/token/`
- `POST /api/auth/token/refresh/`
- `/api/users/`
- `/api/consultants/`
- `/api/categories/`
- `/api/appointments/`
- `/api/projects/`
- `/api/project-applications/`
- `/api/contact-messages/`
