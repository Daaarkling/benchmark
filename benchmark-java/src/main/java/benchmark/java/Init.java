package benchmark.java;

import benchmark.java.output.ConsoleOutput;
import benchmark.java.output.CsvOutput;
import benchmark.java.output.IOutputHandler;
import java.io.File;
import java.io.IOException;
import java.util.Arrays;
import org.apache.commons.cli.CommandLine;
import org.apache.commons.cli.CommandLineParser;
import org.apache.commons.cli.Option;
import org.apache.commons.cli.DefaultParser;
import org.apache.commons.cli.Options;
import org.apache.commons.cli.ParseException;


public class Init {
	
	private static final String OUTPUT_CONSOLE = "console";
	private static final String OUTPUT_CSV = "csv";
	private String[] outputs = {OUTPUT_CONSOLE, OUTPUT_CSV};
	

	public Init(String[] args) {
		
		try {
			Options options = setOptions();
			
			CommandLineParser parser = new DefaultParser();
			CommandLine cmd = parser.parse(options, args);

			String output = OUTPUT_CONSOLE;
			if (cmd.hasOption("r")) {
				String outputGiven = cmd.getOptionValue("r");
				if (Arrays.asList(outputs).contains(outputGiven)) {
					output = outputGiven;
				} else {
					System.err.println("Output must be one of these options: " + String.join(", ", outputs));
					System.exit(1);
				}
			}
			
			String outputDir = ".";
			if (output.equals(OUTPUT_CSV) && cmd.hasOption("d")) {
				outputDir = cmd.getOptionValue("d");
				File outputDirFile = new File(outputDir);
				if (!outputDirFile.isDirectory() || !outputDirFile.canWrite()) {
					System.err.println("Output path is not directory or is not writable.");
					System.exit(1);
				}
			}
			
			Config.Format format = null;
			if (cmd.hasOption("f")) {
				String formatGiven = cmd.getOptionValue("f");
				format = Config.Format.getByName(formatGiven);
				if (format == null) {
					System.err.println("Format must by one of these options: " + String.join(", ", Config.Format.toStringArray()));
					System.exit(1);
				}
			}

			Integer inner = null;
			if (cmd.hasOption("i")) {
				String repGiven = cmd.getOptionValue("i");
				try {
					inner = Integer.valueOf(repGiven);
					if (inner < 1) {
						System.err.println("Number of inner repetitions must be whole number greater than zero.");
						System.exit(1);
					}
				} catch (NumberFormatException ex) {
					System.err.println("Number of inner repetitions must be whole number greater than zero.");
					System.exit(1);
				}
			}
			
			Integer outer = null;
			if (cmd.hasOption("o")) {
				String repGiven = cmd.getOptionValue("o");
				try {
					outer = Integer.valueOf(repGiven);
					if (outer < 1) {
						System.err.println("Number of outer repetitions must be whole number greater than zero.");
						System.exit(1);
					}
				} catch (NumberFormatException ex) {
					System.err.println("Number of outer repetitions must be whole number greater than zero.");
					System.exit(1);
				}
			}

			File testDataFile = new File(Config.testDataPath);
			if (cmd.hasOption("t")) {
				String dataGiven = cmd.getOptionValue("t");
				testDataFile = new File(dataGiven);
			}
			if (!testDataFile.isFile()) {
				System.err.println("Test data file not found.");
				System.exit(1);
			}

			
			Config config = Config.newBuilder(testDataFile)
					.outer(outer)
					.inner(inner)
					.format(format)
					.build();

			
			IOutputHandler outputHandler;
			switch (output) {
				case OUTPUT_CSV:
					outputHandler = new CsvOutput(config.getOuter(), outputDir);
					break;
				default:
					outputHandler = new ConsoleOutput(config.getOuter());
			}
			Benchmark benchmark = new Benchmark(config, outputHandler);
			benchmark.run();

			System.out.println("Benchmark processed successfully!");
			
		} catch (ParseException exp) {
			// oops, something went wrong
			System.err.println("Parsing failed.  Reason: " + exp.getMessage());
			System.exit(1);
		}
	}
	

	
	private Options setOptions() {
		
		Options options = new Options();
		
		Option outputOption = Option.builder("r")
				.hasArg()
				.argName("result")
				.desc("You can choose from several choices: " + String.join(", ", outputs))
				.build();
		options.addOption(outputOption);
		
		Option innerOption = Option.builder("i")
				.hasArg()
				.argName("inner")
				.desc("Number of inner repetitions.")
				.build();
		options.addOption(innerOption);
		
		Option outerOption = Option.builder("o")
				.hasArg()
				.argName("outer")
				.desc("Number of outer repetitions.")
				.build();
		options.addOption(outerOption);
		
		Option dataOption = Option.builder("t")
				.hasArg()
				.argName("data")
				.desc("Test data.")
				.build();
		options.addOption(dataOption);
		
		Option formatOption = Option.builder("f")
				.hasArg()
				.argName("format")
				.desc("Run benchmark for specific format only.")
				.build();
		options.addOption(formatOption);
		
		Option outputDirOption = Option.builder("d")
				.hasArg()
				.argName("out-dir")
				.desc("Output directory.")
				.build();
		options.addOption(outputDirOption);
		
		return options;
	}
	
	
	
	public static void main(String[] args) throws IOException {

		Init init = new Init(args);
	}
}
