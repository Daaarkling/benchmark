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
	protected byte[] dataForDeserialize;


	@Override
	public MetricResult run(Object testData, File testDataFile, int repetitions) {
		
		this.testData = testData;
		this.testDataFile = testDataFile;

		prepareBenchmark();

		MetricResult result = new MetricResult();
		result.setInfo(getInfo());
		Object dataForSerialize = prepareDataForSerialize();
		long start, time;

		try {
			// Serialize
			// Do it once to warm up.
			ByteArrayOutputStream output = new ByteArrayOutputStream();
			boolean outputS = serialize(dataForSerialize, output);
			
			if(outputS) {
				for (int j = 0; j < OUTER_REPETITION; j++){
					start = System.nanoTime();
					for (int i = 0; i < repetitions; i++) {
						serialize(dataForSerialize, new ByteArrayOutputStream());
					}
					time = System.nanoTime() - start;
					result.addSerialize(time);
				}
				dataForDeserialize = output.toByteArray();
				result.setSize(output.size());
				output.close();
			}
		} catch (Exception ex) {
			Logger.getLogger(AMetric.class.getName()).log(Level.SEVERE, null, ex);
		}
		

		try {
			// Deserialize
			dataForDeserialize = prepareDataForDeserialize();
			if (dataForDeserialize == null) {
				return result;
			}			

			// Do it once to warm up.
			Object outputD = deserialize(new ByteArrayInputStream(dataForDeserialize), dataForDeserialize);
			if (outputD != null){
				for (int j = 0; j < OUTER_REPETITION; j++) {
					start = System.nanoTime();
					for (int i = 0; i < repetitions; i++) {
						deserialize(new ByteArrayInputStream(dataForDeserialize), dataForDeserialize);
					}
					time = System.nanoTime() - start;
					result.addDeserialize(time);
				}
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


	/**
	 * Method should return true and write data into output if everything went well
	 * (result will be used for deserialize) or false if serialize is not
	 * implemented, in that case method prepareDataForDeserialize() must be
	 * implemented
	 * 
	 * @param data
	 * @param output
	 * @return
	 * @throws Exception 
	 */
	protected boolean serialize(Object data, OutputStream output) throws Exception {
		return false;
	}


	/**
	 * Method should return Object of deserialized data if everything went well 
	 * or null if deserialize is not implemented
	 * 
	 * @param input
	 * @param bytes
	 * @return
	 * @throws Exception 
	 */
	protected Object deserialize(InputStream input, byte[] bytes) throws Exception {
		return null;
	}
	
	
	protected Object prepareDataForSerialize() {
		return testData;
	}


	/**
	 * If serialize() method is not implemented this method must be otherwise
	 * deserialize() wont proceed
	 *
	 * @return
	 */
	protected byte[] prepareDataForDeserialize() {
		return dataForDeserialize;
	}
}
