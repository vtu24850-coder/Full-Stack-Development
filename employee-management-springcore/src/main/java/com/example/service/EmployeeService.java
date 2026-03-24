package com.example.service;

import com.example.dao.EmployeeDao;
import com.example.model.Employee;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import java.util.List;

@Component
public class EmployeeService {

    private final EmployeeDao employeeDao;

    @Autowired // Demonstrates Dependency Injection
    public EmployeeService(EmployeeDao employeeDao) {
        this.employeeDao = employeeDao;
    }

    public void registerEmployee(Employee employee) {
        employeeDao.addEmployee(employee);
    }

    public Employee findEmployee(int id) {
        return employeeDao.getEmployee(id);
    }

    public List<Employee> findAllEmployees() {
        return employeeDao.getAllEmployees();
    }

    public void removeEmployee(int id) {
        employeeDao.deleteEmployee(id);
    }
}
