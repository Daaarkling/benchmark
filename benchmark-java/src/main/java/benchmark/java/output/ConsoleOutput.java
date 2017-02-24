package benchmark.java.output;

import de.vandermeer.asciitable.v2.RenderedTable;
import de.vandermeer.asciitable.v2.V2_AsciiTable;
import de.vandermeer.asciitable.v2.render.V2_AsciiTableRenderer;
import de.vandermeer.asciitable.v2.render.WidthLongestLine;
import de.vandermeer.asciitable.v2.themes.V2_E_TableThemes;
import java.io.PrintStream;
import java.util.Iterator;
import java.util.List;
import java.util.Map;


public class ConsoleOutput extends AOutputHandler {

	public static PrintStream printStream = System.out;

	public ConsoleOutput(int outer) {
		super(outer);
	}
	
	
	
	
	@Override
	protected void printOutput(String name, List<String> headers, List<List<String>> rows) {

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

		RenderedTable renderedTable = renderer.render(table);
		
		printStream.println("");
		printStream.println(name);
		printStream.println("");
		printStream.println(renderedTable);
		printStream.println("");
	}


	
	@Override
	public void printBenchmarkInfo(Map<String, String> info) {
		
		printStream.println("Benchmark info");
		printStream.println();
		Iterator it = info.entrySet().iterator();
		while (it.hasNext()) {
			Map.Entry pair = (Map.Entry) it.next();
			printStream.println(pair.getKey() + ": " + pair.getValue());
			it.remove();
		}
		printStream.println();
	}
}
