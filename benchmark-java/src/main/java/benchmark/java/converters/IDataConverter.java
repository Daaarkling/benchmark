package benchmark.java.converters;

import java.io.File;


public interface IDataConverter {
	
	/**
	 * Convert given test data into desirable format such as json, xml, pojo,...
	 * 
	 * @param testDataFile
	 * @return data in desirable format json, xml, pojo,...
	 */
	public Object convertData(File testDataFile);
}
