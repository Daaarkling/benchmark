package benchmark.java.utils;

import java.util.ArrayList;
import java.util.List;
import java.util.Set;
import org.reflections.Reflections;
import benchmark.java.metrics.IMetric;



public class MetricClassLoader {
	
	
	 public List<IMetric> loadMetricClasses() {

		Reflections reflections = new Reflections("benchmark.java.metrics.");
		Set<Class<? extends IMetric>> classes = reflections.getSubTypesOf(IMetric.class);
		List<IMetric> objects = new ArrayList<>();
		for (Class<? extends IMetric> clazz : classes) {
			IMetric object = instantiateClass(clazz.getName());
			if (object != null) {
				objects.add(object);
			}
		}
		return objects;
	}
	
	
	
	/**
	 * Attempt to create object from given className and check if implements instanceof
	 * 
	 * @param className the fully qualified name of the class
	 * @return Object can be cast into interfaceName
	 */
	public IMetric instantiateClass(String className) {

		try {
			Class clazz = Class.forName(className);
			if(IMetric.class.isAssignableFrom(clazz)) {
				return (IMetric) clazz.newInstance();
			}
			return null;
		} catch (ClassNotFoundException | InstantiationException | IllegalAccessException | NoClassDefFoundError ex) {
			return null;
		}
	}		
}
