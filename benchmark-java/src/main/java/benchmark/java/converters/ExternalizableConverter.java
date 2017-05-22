package benchmark.java.converters;

import benchmark.java.metrics.jnative.PersonCollection;
import java.io.File;
import java.io.IOException;
import java.util.logging.Level;
import java.util.logging.Logger;
import com.fasterxml.jackson.databind.ObjectMapper;


public class ExternalizableConverter implements IDataConverter {
	
	/**
	 *
	 * @param testDataFile
	 * @return PersonCollection POJO object
	 */
	@Override
	public Object convertData(File testDataFile) {

		try {
			ObjectMapper mapper = new ObjectMapper();
			PersonCollection collection = mapper.readValue(testDataFile, PersonCollection.class);
			return collection;
		} catch (IOException ex) {
			Logger.getLogger(PojoConverter.class.getName()).log(Level.SEVERE, null, ex);
			return null;
		}
	}
}
