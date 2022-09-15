# Staging setup

1. Copy `setup` folder to server.

    ```bash
    scp -r ./docs/setup user@new.server.com:/tmp/
    ```

2. SHH to server and run setup script with hostname

    ```bash
    sudo /tmp/setup/staging.sh stg.scholarshipowl.com
    ```

3. Prepare new server for deployment

    TODO: Check coping Public SSH keys from remote source or even git.

    * Add your public key to `/home/deploy/.ssh/authorized_keys`_
    * Create new server config for capistranno deploy and place it into
    `/config/deploy` folder

4. Import database dump to server
    * Download dump file to new server
    * Run it with:
     `mysql -uroot -p65vu7sgeKkEQaXGb scholarship_owl < dump.sql`

5. Deploy code to new server
