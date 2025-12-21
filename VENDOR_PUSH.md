# Push Vendor to Git Repository

Setelah folder `vendor/` di-remove dari `.gitignore`, folder ini sekarang akan ter-track oleh git.

## Add Vendor ke Git

```bash
# Add vendor folder
git add vendor/

# Commit changes
git commit -m "Add vendor folder to repository for easier deployment"

# Push to development branch
git push origin development
```

## Verify

Cek apakah vendor sudah ter-push:
```bash
git ls-files vendor/ | head -20
```

Jika ada output, berarti vendor sudah ter-track dan akan ter-push ke repo.

## Catatan

Dengan vendor ter-push ke repo, deployment di Hostinger menjadi lebih simple:
1. Pull repo → semua file langsung available
2. Setup .env → copy dari .env.production  
3. Set permissions → chmod folders
4. Done ✅

Tidak perlu lagi install dependencies manual atau via composer di server.
