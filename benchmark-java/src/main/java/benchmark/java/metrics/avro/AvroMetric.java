package benchmark.java.metrics.avro;

import java.io.InputStream;
import java.io.OutputStream;
import org.apache.avro.file.DataFileStream;
import org.apache.avro.file.DataFileWriter;
import org.apache.avro.io.DatumReader;
import org.apache.avro.io.DatumWriter;
import org.apache.avro.specific.SpecificDatumReader;
import org.apache.avro.specific.SpecificDatumWriter;
import benchmark.java.Config;
import benchmark.java.converters.AvroConverter;
import benchmark.java.converters.IDataConverter;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;


public class AvroMetric extends AMetric {
	
	private DataFileWriter<PersonCollection> dataFileWriter;
	private DatumReader<PersonCollection> datumReader;



	@Override
	public Info getInfo() {
		return new Info(Config.Format.AVRO, "Avro", "https://avro.apache.org/", "1.8.1");
	}

	
	@Override
	protected void prepareBenchmark() {
		
		DatumWriter<PersonCollection> userDatumWriter = new SpecificDatumWriter<>(PersonCollection.class);
		dataFileWriter = new DataFileWriter<>(userDatumWriter);
		datumReader = new SpecificDatumReader<>(PersonCollection.class);
	}

	@Override
	protected Object prepareTestDataForSerialize() {
		
		IDataConverter convertor = new AvroConverter();
		return convertor.convertData(testDataFile);
	}
	
	
	
	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {
		
		PersonCollection persons = (PersonCollection) data;
		dataFileWriter.create(persons.getSchema(), output);
		dataFileWriter.append(persons);
		dataFileWriter.close();
		return true;
	}

	
	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {

		DataFileStream<PersonCollection> dataReader = new DataFileStream<>(input, datumReader);
		PersonCollection personCollection = dataReader.next();
		return personCollection;
	}

	
}
