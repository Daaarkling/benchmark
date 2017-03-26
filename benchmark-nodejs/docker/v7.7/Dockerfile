FROM node:7.7


WORKDIR /opt


# COPY or ADD can't be used with ../ -> Forbidden path outside the build context
# therefore git is used
RUN git clone https://github.com/Daaarkling/benchmark.git


WORKDIR	benchmark/benchmark-nodejs


RUN npm install
