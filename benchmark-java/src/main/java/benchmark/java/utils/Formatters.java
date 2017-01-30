package benchmark.java.utils;


public class Formatters {
	
	/**
	 * @link https://latte.nette.org/cs/filters#toc-bytes
	 *
	 * Converts to human readable file size and add units
	 * @param  bytes
	 * @param  precision
	 * @return String
	 */
	public static String bytes(float bytes, int precision) {

		String unitGo = "";
		String[] units = {"B", "kB", "MB", "GB", "TB", "PB"};
		for (String unit : units) {
			if (Math.abs(bytes) < 1024 || unit.equals(units[units.length - 1])) {
				unitGo = unit;
				break;
			}
			bytes = (float) bytes / 1024;
		}
		double prec = Math.pow(10, precision);
		return Math.round(bytes * prec) / prec + " " + unitGo;
	}
	
	public static String bytes(int bytes) {
		
		return bytes(bytes, 2);
	}
	
	
	/**
	 * Converts seconds to human readable format and add units
	 *
	 * @param  nanoseconds
	 * @param  precision
	 * @return string
	 */
	public static String seconds(Long nanoseconds, int precision) {
		
		float time = nanoseconds;
		String unitGo = "";
		String[] units = {"ns", "μs", "ms", "s"};
		for (String unit : units) {
			if (Math.abs(time) < 1000 || unit.equals(units[units.length - 1])) {
				unitGo = unit;
				break;
			}
			time = (float) time / 1000;
		}
		double prec = Math.pow(10, precision);
		return Math.round(time * prec) / prec + " " + unitGo;
	}

	public static String seconds(Long nanoseconds) {
		
		return seconds(nanoseconds, 5);
	}
}
