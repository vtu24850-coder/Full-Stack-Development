package com.example.dao;

import com.example.model.Employee;
import java.util.List;

public interface EmployeeDao {
    void addEmployee(Employee employee);
    Employee getEmployee(int id);
    List<Employee> getAllEmployees();
    void deleteEmployee(int id);
}
