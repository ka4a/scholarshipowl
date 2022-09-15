FROM redash/redash:5.0.1.b4850

USER root

# Update OpenSLL version. Version with TLSv1.0 is depricated by SparkPost
RUN cd /usr/src && \
    wget https://www.openssl.org/source/openssl-1.1.1.tar.gz && \
    tar -zxf openssl-1.1.1.tar.gz && \
    cd openssl-1.1.1 && \
    ./config  && \
    make && make install && \
    echo '/usr/local/lib' >> /etc/ld.so.conf && \
    ldconfig && \
    echo 'export LD_LIBRARY_PATH=/usr/local/lib' >> ~/.bash_profile && . ~/.bash_profile && \
    mv /usr/bin/openssl /root/ && \
    ln -s /usr/local/ssl/bin/openssl /usr/bin/openssl && \
    openssl version

COPY docker-entrypoint /app/bin/docker-entrypoint
RUN chmod +x /app/bin/docker-entrypoint

USER redash

ENTRYPOINT ["/app/bin/docker-entrypoint"]
CMD ["server"]