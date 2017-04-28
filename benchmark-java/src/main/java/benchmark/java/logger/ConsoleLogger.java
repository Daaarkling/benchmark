package benchmark.java.logger;

import benchmark.java.metrics.Info;
import java.io.PrintStream;
import java.sql.Timestamp;


public class ConsoleLogger implements ILogger {
	
	private final PrintStream printStream;

	public ConsoleLogger(PrintStream printStream) {
		this.printStream = printStream;
	}
	
	@Override
	public void startMessage(Info info) {
		
		String timestamp = getCurrentTimestamp();
		printStream.println("[INFO] " + timestamp + " Benchmark of \"" + info.getFullName() + "\" has started.");
	}

	@Override
	public void endMessage(Info info) {
		
		String timestamp = getCurrentTimestamp();
		printStream.println("[INFO] " + timestamp + " Benchmark of \"" + info.getFullName() + "\" has finished.");
	}
	
	private String getCurrentTimestamp() {
		
		Timestamp timestamp = new Timestamp(System.currentTimeMillis());
		return timestamp.toString();
	}
}
