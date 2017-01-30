package benchmark.java.metrics;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author Jan
 */
public abstract class AMetric implements IMetric {

	protected Object testData;
	protected File testDataFile;


	@Override
	public MetricResult run(Object testData, File testDataFile, int repetitions) {
		
		this.testData = testData;
		this.testDataFile = testDataFile;

		prepareBenchmark();

		MetricResult result = new MetricResult();
		result.setInfo(getInfo());
		Object dataForEncode = prepareTestDataForSerialize();
		byte[] dataForDecode;
		boolean encodeImplemented = false;
		Object decodeImplemented = null;
		long start, time;

		try {
			// Serialize
			// Do it once to warm up.
			serialize(dataForEncode, new ByteArrayOutputStream());
		//	output.reset();

			start = System.nanoTime();
			for (int i = 1; i <= repetitions; i++) {
				encodeImplemented = serialize(dataForEncode, new ByteArrayOutputStream());
			}
			time = System.nanoTime() - start;

			if (encodeImplemented) {
				result.setSerialize(time);
				
				// Deserialize just one data not multiple data stacked on each other
				ByteArrayOutputStream output = new ByteArrayOutputStream();
				serialize(dataForEncode, output);
				result.setSize(output.size());
				dataForDecode = output.toByteArray();
				output.close();
			} else {
				dataForDecode = prepareTestDataForDeserialize();
				if (dataForDecode == null) {
					return result;
				}
			}
			

		} catch (Exception ex) {
			Logger.getLogger(AMetric.class.getName()).log(Level.SEVERE, null, ex);

			dataForDecode = prepareTestDataForDeserialize();
			if (dataForDecode == null) {
				return result;
			}
		}

		try {
			// Deserialize
			// Do it once to warm up.
			deserialize(new ByteArrayInputStream(dataForDecode), dataForDecode);

			start = System.nanoTime();
			for (int i = 1; i <= repetitions; i++) {
				decodeImplemented = deserialize(new ByteArrayInputStream(dataForDecode), dataForDecode);
			}
			time = System.nanoTime() - start;

			if (decodeImplemented != null) {
				result.setDeserialize(time);
			}
		} catch (Exception ex) {
			Logger.getLogger(AMetric.class.getName()).log(Level.SEVERE, null, ex);
		}

		return result;
	}


	/**
	 * Its called once before serialize()
	 */
	protected void prepareBenchmark(){
	}

	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {
		return false;
	}

	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		return null;
	}
	
	
	protected Object prepareTestDataForSerialize() {
		return this.testData;
	}


	protected byte[] prepareTestDataForDeserialize() {
		return null;
	}


}
