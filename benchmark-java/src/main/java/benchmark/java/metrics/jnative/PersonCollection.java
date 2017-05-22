
package benchmark.java.metrics.jnative;

import java.io.Externalizable;
import java.io.IOException;
import java.io.ObjectInput;
import java.io.ObjectOutput;
import java.util.ArrayList;
import java.util.List;


public class PersonCollection implements Externalizable {
	
	private static final long serialVersionUID = 5356891911888315506L;
	private List<Person> persons = new ArrayList<>();

	@Override
	public void writeExternal(ObjectOutput out) throws IOException {
		out.writeObject(persons);
	}

	@Override
	public void readExternal(ObjectInput in) throws IOException, ClassNotFoundException {
		persons = (List<Person>) in.readObject();
	}

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