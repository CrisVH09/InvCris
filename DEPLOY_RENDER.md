# Despliegue en Render con TiDB Cloud

## 1. Crear la base de datos en TiDB Cloud

1. En TiDB Cloud, crea una instancia **Starter** (o usa una existente).
2. Abre la instancia y pulsa **Connect**.
3. Selecciona **Public Endpoint** y genera una contraseña si aún no existe.
4. Guarda por separado los valores de host, puerto, base de datos, usuario y contraseña.
   El nombre de usuario debe copiarse completo, incluido el prefijo que muestra TiDB.

TiDB Starter/Essential exige TLS. La imagen Docker ya instala los certificados raíz y
Laravel usará `/etc/ssl/certs/ca-certificates.crt` para verificar la conexión.

## 2. Generar la clave de Laravel

Ejecuta localmente:

```powershell
php artisan key:generate --show
```

Copia el resultado completo, incluido el prefijo `base64:`. No lo agregues a ningún
archivo versionado.

## 3. Publicar los cambios en GitHub

Antes de hacer commit, comprueba que `.env` no aparezca en `git status`.

```powershell
git add .dockerignore .env.example config/services.php dockerfile docker render.yaml DEPLOY_RENDER.md storage/app/public
git commit -m "Configurar despliegue en Render con TiDB Cloud"
git push origin main
```

## 4. Crear el servicio en Render

1. En Render, elige **New > Blueprint**.
2. Conecta el repositorio `CriCri-Dev/Spidy`.
3. Render detectará `render.yaml` y solicitará las variables marcadas como secretas.
4. Ingresa:

| Variable | Valor |
|---|---|
| `APP_KEY` | Salida completa de `php artisan key:generate --show` |
| `APP_URL` | URL final, por ejemplo `https://spidy.onrender.com` |
| `DB_HOST` | Host del Public Endpoint de TiDB |
| `DB_DATABASE` | Base indicada por TiDB, normalmente `test` |
| `DB_USERNAME` | Usuario completo mostrado por TiDB |
| `DB_PASSWORD` | Contraseña generada en TiDB |
| `RSVP_ADMIN_KEY` | Una contraseña larga y exclusiva para el panel RSVP |

El puerto `4000`, el controlador MySQL y la ruta del certificado TLS ya están definidos.
Si el cuadro **Connect** de tu instancia muestra otro puerto, cambia `DB_PORT` en Render.
El Blueprint fija `plan: free`, por lo que no debe crear una instancia Starter de pago.

## 5. Verificar

El contenedor ejecuta `php artisan migrate --force` antes de iniciar Apache. Cuando el
deploy termine:

- abre la URL pública;
- comprueba que `https://TU-DOMINIO/up` responda correctamente;
- envía una confirmación de prueba;
- entra en `/protocolo-traje` con el valor de `RSVP_ADMIN_KEY` y confirma que aparece.

Si el deploy falla, revisa primero los logs. Los errores `Connection refused`, TLS o
`Access denied` suelen indicar que alguno de los cinco valores `DB_*` no coincide
exactamente con el cuadro **Connect** de TiDB.
