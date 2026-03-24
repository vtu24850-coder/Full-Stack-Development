package com.example.dao;

import com.example.model.Employee;
import org.springframework.stereotype.Component;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

@Component
public class EmployeeDaoImpl implements EmployeeDao {
    private Map<Integer, Employee> employeeStorage = new HashMap<>();

    @Override
    public void addEmployee(Employee employee) {
        employeeStorage.put(employee.getId(), employee);
        System.out.println("Employee added: " + employee.getName());
    }

    @Override
    public Employee getEmployee(int id) {
        return employeeStorage.get(id);
    }

    @Override
    public List<Employee> getAllEmployees() {
        return new ArrayList<>(employeeStorage.values());
    }

    @Override
    public void deleteEmployee(int id) {
        employeeStorage.remove(id);
        System.out.println("Employee deleted with id: " + id);
    }
}
