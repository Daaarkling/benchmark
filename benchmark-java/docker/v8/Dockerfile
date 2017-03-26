FROM maven:3.3-jdk-8


# Install mono (http://www.mono-project.com)
RUN apt-get update && apt-get install -y --no-install-recommends \ 
	mono-complete

	
WORKDIR /opt


# COPY or ADD can't be used with ../ -> Forbidden path outside the build context
# therefore git is used
RUN git clone https://github.com/Daaarkling/benchmark.git


WORKDIR	benchmark/benchmark-java
 

RUN mvn package
