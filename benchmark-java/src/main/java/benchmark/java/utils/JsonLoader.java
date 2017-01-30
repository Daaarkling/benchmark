package benchmark.java.utils;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import java.io.File;
import java.io.IOException;
import java.io.InputStream;



public class JsonLoader {
	

    private JsonLoader()
    {
    }

    /**
     * Load one resource from the current package as a {@link JsonNode}
     *
     * @param name name of the resource
     * @return a JSON document
     * @throws IOException resource not found
     */
    public static JsonNode loadResource(final String name) throws IOException {
		
		File file = new File(name);
		return loadResource(file);
    }
	
	
	/**
	 * Load one resource from the current package as a {@link JsonNode}
	 *
	 * @param file resource
	 * @return a JSON document
	 * @throws IOException resource not found
	 */
	public static JsonNode loadResource(File file) throws IOException {

		if (!file.isFile()) {
			throw new IOException("File " + file.getName() + " was not found.");
		}
		ObjectMapper mapper = new ObjectMapper();
		return mapper.readTree(file);
	}
	
	

	public static JsonNode loadResource(InputStream input) throws IOException {

		ObjectMapper mapper = new ObjectMapper();
		return mapper.readTree(input);
	}
	
}
