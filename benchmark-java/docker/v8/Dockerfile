FROM maven:3.3-jdk-8

# Install mono (http://www.mono-project.com)
RUN apt-get update && apt-get install -y --no-install-recommends \ 
	mono-complete

COPY ./ /opt/benchmark/benchmark-java

WORKDIR	/opt/benchmark/benchmark-java

RUN mvn package