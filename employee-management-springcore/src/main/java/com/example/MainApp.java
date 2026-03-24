package com.example;

import com.example.config.AppConfig;
import com.example.model.Employee;
import com.example.service.EmployeeService;
import org.springframework.beans.factory.BeanFactory;
import org.springframework.context.annotation.AnnotationConfigApplicationContext;

public class MainApp {
    public static void main(String[] args) {
        System.out.println("--- Starting Employee Management System ---");
        
        // Use BeanFactory to manage beans
        // AnnotationConfigApplicationContext implements BeanFactory and allows annotation-based configuration
        BeanFactory factory = new AnnotationConfigApplicationContext(AppConfig.class);
        
        // Retrieve EmployeeService bean from the BeanFactory
        EmployeeService employeeService = factory.getBean(EmployeeService.class);

        // Demonstrating in-memory employee storage
        employeeService.registerEmployee(new Employee(1, "Alice Smith", "Engineering", 85000));
        employeeService.registerEmployee(new Employee(2, "Bob Jones", "Human Resources", 60000));
        employeeService.registerEmployee(new Employee(3, "Charlie Brown", "Marketing", 70000));

        System.out.println("\n--- All Employees ---");
        for (Employee emp : employeeService.findAllEmployees()) {
            System.out.println(emp);
        }

        System.out.println("\n--- Find Employee with ID: 2 ---");
        System.out.println(employeeService.findEmployee(2));
        
        System.out.println("\n--- Remove Employee with ID: 2 ---");
        employeeService.removeEmployee(2);
        
        System.out.println("\n--- All Employees After Removal ---");
        for (Employee emp : employeeService.findAllEmployees()) {
            System.out.println(emp);
        }
    }
}
