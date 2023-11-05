# Use an official PHP runtime as a parent image
FROM php:7.4-apache

# Set the working directory in the container to /var/www/html
WORKDIR /var/www/html

# Copy the current directory contents into the container at /var/www/html
COPY . /var/www/html

# Install any needed packages specified in requirements.txt
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

RUN a2enmod rewrite
RUN a2enmod headers

# Make port 80 available to the world outside this container
EXPOSE 80

# Run apache2 in the foreground
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]