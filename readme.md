# FAQ

### Question 
Fatal error: Class 'finfo' not found

###  Answer

#### Windows

Pengguna Windows, aktifkan fileinfo extension di `php\php.ini`

Buka file php.ini, cari

```
;extension=php_fileinfo.dll
```

hilangkan tanda ; hingga menjadi

```
extension=php_fileinfo.dll
```

Restart web server.

#### *nix

Pengguna *nix, file `php.ini` biasanya ada di `/etc`. Lokasi file untuk mengaktifkan php extension mungkin berbeda-beda pada tiap distro.

Untuk pengguna Debian/Ubuntu bisa menggunakan `sudo php5enmod fileinfo`