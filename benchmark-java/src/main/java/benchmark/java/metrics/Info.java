package benchmark.java.metrics;

import benchmark.java.Config;


/**
 *
 * @author Jan
 */
public class Info {
	
	private Config.Format format;
	private String name;
	private String url;
	private String version;

	public Info(Config.Format format, String name, String url, String version) {
		this.format = format;
		this.name = name;
		this.url = url;
		this.version = version;
	}

	public Info(Config.Format format, String name, String url) {
		this.format = format;
		this.name = name;
		this.url = url;
	}
	

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getUrl() {
		return url;
	}

	public void setUrl(String url) {
		this.url = url;
	}

	public String getVersion() {
		return version;
	}

	public void setVersion(String version) {
		this.version = version;
	}

	public Config.Format getFormat() {
		return format;
	}

	public void setFormat(Config.Format format) {
		this.format = format;
	}

	public String getFullName() {
		if (version == null || "".equals(version)){
			return format + " - " + name;
		}
		return format + " - " + name + " " + version;
	}
	
	
}
