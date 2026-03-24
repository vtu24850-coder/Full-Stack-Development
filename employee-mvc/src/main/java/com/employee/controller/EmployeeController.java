package com.employee.controller;

import com.employee.model.Employee;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;

import java.util.Arrays;
import java.util.List;

@Controller
@RequestMapping("/")
public class EmployeeController {

    @GetMapping
    public String showIndex() {
        return "index";
    }

    @GetMapping("/employees")
    public String showEmployees(Model model) {
        List<Employee> employees = Arrays.asList(
                new Employee(1, "Alice Smith", "Engineering", 85000),
                new Employee(2, "Bob Johnson", "HR", 65000),
                new Employee(3, "Charlie Brown", "Finance", 75000)
        );
        model.addAttribute("employees", employees);
        return "employeeDetails";
    }
}
