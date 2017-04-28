package benchmark.java.logger;

import benchmark.java.metrics.Info;

public interface ILogger {
	
	public void startMessage(Info info);
	
	public void endMessage(Info info);
}
