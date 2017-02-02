package benchmark.java.metrics.protobuf;

import java.io.InputStream;
import java.io.OutputStream;
import benchmark.java.Config;
import benchmark.java.converters.IDataConverter;
import benchmark.java.converters.ProtobufConverter;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;


public class ProtobufMetric extends AMetric {

	
	@Override
	public Info getInfo() {
		return new Info(Config.Format.PROTOBUF, "Google Protobuf", "https://github.com/google/protobuf", "3.1.0");
	}
	
	
	@Override
	protected Object prepareTestDataForSerialize() {
	
		IDataConverter convertor = new ProtobufConverter();
		return convertor.convertData(testDataFile);
	}


	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {
	
		PersonCollectionOuterClass.PersonCollection personCollection = (PersonCollectionOuterClass.PersonCollection) data;
		personCollection.writeTo(output);
		return true;
	}

	
	
	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		PersonCollectionOuterClass.PersonCollection personCollection = PersonCollectionOuterClass.PersonCollection.parseFrom(input);
		return personCollection;
	}
}
