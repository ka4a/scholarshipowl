# Dovecot (IMAP) server install on development

## Install
`sudo aptitude install dovecot-imapd`

## Configurations

### Conection

  - Remove '#' from line `# listen = *, ::` at `/etc/dovecot/dovecot.conf`
  
### Authentication

  - `useradd -g mail all && echo all:secret | chpasswd`
