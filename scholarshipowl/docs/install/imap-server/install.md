# Setup IMAP server

## Install

```
sudo apt install dovecot-core dovecot-imapd
```

### SSL Self-signed

Edit `/usr/share/dovecot/dovecot-openssl.cnf` with your configs.

Run `/usr/share/dovecot/mkcert.sh`

Enable SSL configs at `/etc/dovecot/conf.d/10-ssl.cnf`
