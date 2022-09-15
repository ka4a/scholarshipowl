#!/usr/bin/env bash

if [ "$#" -ne 2 ]; then
  echo "Usage: $0 host path";
  exit 1;
fi;

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

SOWL_CONFIG=/etc/nginx/sites-available/scholarshipowl
SOWL_CONFIG_LINK=/etc/nginx/sites-enabled/scholarshipowl

cp ${DIR}/config/scholarshipowl.nginx.conf ${SOWL_CONFIG}

sed -i -e "s#<<HOST>>#$1#g" ${SOWL_CONFIG}
sed -i -e "s#<<PATH>>#$2#g" ${SOWL_CONFIG}

rm -f /etc/nginx/sites-enabled/default
test -e ${SOWL_CONFIG_LINK} && unlink ${SOWL_CONFIG_LINK}
ln -s ${SOWL_CONFIG} ${SOWL_CONFIG_LINK}
