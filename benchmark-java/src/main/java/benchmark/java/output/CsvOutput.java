package benchmark.java.output;

import static benchmark.java.output.ConsoleOutput.printStream;
import com.opencsv.CSVWriter;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintStream;
import java.io.UnsupportedEncodingException;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.logging.Level;
import java.util.logging.Logger;


public class CsvOutput extends AOutputHandler {

	private final String outDir;

	
	public CsvOutput(String outDir) {
		this.outDir = outDir;
	}
	
	
	
	@Override
	protected void printOutput(String name, List<String> headers, List<List<String>> rows) {
		
		try {
			name = outDir + File.separator + name.replace(" ", "-") + ".csv";
			CSVWriter writer = new CSVWriter(new FileWriter(name), ';');
			String[] entries = new String[headers.size()];
			headers.toArray(entries);
			writer.writeNext(entries);

			for (List<String> row : rows) {
				entries = new String[row.size()];
				row.toArray(entries);
				writer.writeNext(entries);
			}
			writer.close();
		} catch (IOException ex) {
			Logger.getLogger(CsvOutput.class.getName()).log(Level.SEVERE, null, ex);
		}
	}


	
	@Override
	public void printBenchmarkInfo(Map<String, String> info) {
		
		PrintStream printStream = null;
		try {
			String outputFile = outDir + File.separator + "java-info.txt";
			printStream = new PrintStream(outputFile, "UTF-8");
			Iterator it = info.entrySet().iterator();
			while (it.hasNext()) {
				Map.Entry pair = (Map.Entry) it.next();
				printStream.println(pair.getKey() + ": " + pair.getValue());
				it.remove();
			}
		} catch (FileNotFoundException | UnsupportedEncodingException ex) {
			Logger.getLogger(CsvOutput.class.getName()).log(Level.SEVERE, null, ex);
		} finally {
			printStream.close();
		}
	}
	
	
}
