FROM mysql:5.7

ARG SQLFILENAME=scholarship.sql
ENV SQLFILENAME ${SQLFILENAME}

COPY ${SQLFILENAME} /docker-entrypoint-initdb.d/sowl.db.sql
RUN chmod -R 775 /docker-entrypoint-initdb.d

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["mysqld"]