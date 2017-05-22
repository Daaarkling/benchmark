
package benchmark.java.entities;

import com.bluelinelabs.logansquare.annotation.JsonObject;
import com.bluelinelabs.logansquare.annotation.JsonObject.FieldDetectionPolicy;
import com.dslplatform.json.CompiledJson;
import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;
import javax.xml.bind.annotation.XmlRootElement;

@XmlRootElement
@CompiledJson
@JsonObject(fieldDetectionPolicy = FieldDetectionPolicy.NONPRIVATE_FIELDS_AND_ACCESSORS)
public class PersonCollection implements Serializable {
	
	private static final long serialVersionUID = 7058184852008081269L;

	private List<Person> persons = new ArrayList<>();

   
	public void addPerson(Person person) {
		
		persons.add(person);
	}

	public List<Person> getPersons() {
		return persons;
	}

	public void setPersons(List<Person> persons) {
		this.persons = persons;
	}
	
	

}