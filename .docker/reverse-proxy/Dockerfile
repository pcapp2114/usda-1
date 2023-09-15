FROM nginx:latest

RUN apt-get update
RUN apt-get -y install vim && \
    apt-get -y install bash

#RUN apt-get --only-upgrade install openssl
#RUN apt-get -y update
#RUN apt-get -y dist-upgrade

COPY webmod-local-reverse-proxy-nginx.conf /etc/nginx/conf.d/default.conf

COPY generate-self-signed-cert.sh /usr/local/bin
RUN chmod +x /usr/local/bin/generate-self-signed-cert.sh
RUN /usr/local/bin/generate-self-signed-cert.sh
RUN rm /usr/local/bin/generate-self-signed-cert.sh

EXPOSE 80
EXPOSE 443

WORKDIR /usr/share/nginx/html
CMD ["nginx", "-g", "daemon off;"]
