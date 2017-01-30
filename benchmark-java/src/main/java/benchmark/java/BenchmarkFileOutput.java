package benchmark.java;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.PrintStream;
import java.io.UnsupportedEncodingException;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;


public class BenchmarkFileOutput extends BenchmarkConsoleOutput {

	public static String fileName = "java-benchmark-output.txt";
	private final String outputDir;

	
	public BenchmarkFileOutput(Config config, String outputDir) {
		super(config);
		this.outputDir = outputDir;
	}

	@Override
	protected void createPrintStream() {
		
		try {
			String outputFile = outputDir + File.separator + fileName;
			printStream = new PrintStream(outputFile, "UTF-8");

		} catch (FileNotFoundException | UnsupportedEncodingException ex) {
			Logger.getLogger(BenchmarkFileOutput.class.getName()).log(Level.SEVERE, null, ex);

			System.err.println(ex.getMessage());
			System.exit(1);
		}
	}
	
	
	

	@Override
	protected void handleResult(List<String> headers, List<List<String>> rows) {
		super.handleResult(headers, rows);
		printStream.close();
	}
}
