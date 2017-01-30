package benchmark.java;

import de.vandermeer.asciitable.v2.RenderedTable;
import de.vandermeer.asciitable.v2.V2_AsciiTable;
import de.vandermeer.asciitable.v2.render.V2_AsciiTableRenderer;
import de.vandermeer.asciitable.v2.render.WidthLongestLine;
import de.vandermeer.asciitable.v2.themes.V2_E_TableThemes;
import java.io.PrintStream;
import java.util.Iterator;
import java.util.List;


public class BenchmarkConsoleOutput extends Benchmark {

	protected PrintStream printStream;
	
	public BenchmarkConsoleOutput(Config config) {
		super(config);
	}

	protected void createPrintStream() {
		printStream = System.out;
	}
	
	
	@Override
	protected void handleResult(List<String> headers, List<List<String>> rows) {
		
		createPrintStream();
		
		V2_AsciiTable table = new V2_AsciiTable();

		table.addRow(headers.toArray());
		table.addStrongRule();

		Iterator<List<String>> timesIterator = rows.iterator();
		while (timesIterator.hasNext()) {
			table.addRow(timesIterator.next().toArray());
			if (timesIterator.hasNext()) {
				table.addRule();
			}
		}

		V2_AsciiTableRenderer renderer = new V2_AsciiTableRenderer();
		renderer.setTheme(V2_E_TableThemes.PLAIN_7BIT_STRONG.get());
		renderer.setWidth(new WidthLongestLine());
		
		printTable(renderer.render(table));
	}

	
	protected void printTable(RenderedTable renderedTable) {
		
		printStream.println("");
		printStream.println(renderedTable);
		printStream.println("");
	}

	
}
