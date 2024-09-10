# genera la chiave per il certificato CA
if [ ! -f CA-key.pem ]; then
    echo "Genero chiave CA"
    openssl genrsa -aes256 -out CA-key.pem 4096
fi

# genera il certificato della CA
if [ ! -f CA.pem ]; then
    clear
    echo "Dati della CA"
    openssl req -new -x509 -sha256 -days 3650 -key CA-key.pem -out CA.pem -addext 'subjectAltName = DNS:acsoft.top'
fi

#crea una chiave privata per il web server
openssl genrsa -out certificati/key.key 4096

#crea un csr
clear
echo "Dati del certificato server"
openssl req -new -sha256 -key certificati/key.key -out internal.csr

# cea il certificato per il web server
openssl x509 -req -sha256 -days 3650 -in internal.csr -CA CA.pem -CAkey CA-key.pem -out certificati/cert.crt -extfile certconf.cnf
