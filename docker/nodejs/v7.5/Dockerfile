FROM node:7.5
WORKDIR /opt
RUN git clone https://github.com/Daaarkling/benchmark.git
WORKDIR benchmark/benchmark-nodejs
RUN npm install
RUN mkdir output
CMD node init.js -r $repetition -f $format -o csv -d ./output
