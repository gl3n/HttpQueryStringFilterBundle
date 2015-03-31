# HttpQueryStringFilterBundle

Easy **validation**, **casting** and **authorization** of HTTP query string parameters.

## 1. Installation

Install the bundle through composer, then add it to your ``AppKernel.php`` file :

```php
$bundles = array(
    // ...
    new Gl3n\HttpQueryStringFilterBundle\Gl3nHttpQueryStringFilterBundle(),
);
```

## 2. Configuration

Here is a sample filters configuration :

```yml
gl3n_http_query_string_filter:
    filters:
        list:
            params:
                size: { type: string, reg_exp: '^s|m|l$', default: 's' }
        post_list:
            include: [list]
            params:
                userId:  { type: integer }
                isDraft: { type: boolean, roles: [ROLE_ADMIN] }
                tagIds:  { type: integer, array: true, default: '[]' }
```

A filter has parameters (``params`` node). It can also include other filters' parameters (``include`` node).

### Parameter configuration

Each parameter configuration has 6 options :

| Name         | Required | Description                                                                             |
|--------------|----------|-----------------------------------------------------------------------------------------|
| ``type``     | yes      | ``string``, ``boolean`` or ``integer`` *(see below for detail)*                         |
| ``reg_exp``  | no       | The regular expression to use for validation *(default depends on the parameter type)*  |
| ``default``  | no       | Default value *(will be casted depending on the parameter type)*                        |
| ``required`` | no       | Boolean that indicates whether the parameter is required *(default is false)*           |
| ``array``    | no       | Boolean that indicates wheter expected parameter value is an array *(default is false)* |
| ``roles``    | no       | An array of security roles allowed to use this parameter *(default is an empty array)*              |

#### Default value in the case of array parameters

In this special case, default value is evaluated through Symfony **Expression Language** component. Then if the result in an array : each value is casted depending on the configured parameter type.

You can see an example of this case in the sample configuration above.

## 3. Parameter types

### Definition

A parameter type defines :

1. how the **value** (or the **values** in the case of a parameter configured as an array) will be **casted**,
2. the **default regular expression**.

### Reference

| Type        | Cast to     | Default reg exp  |
|-------------|-------------|------------------|
| **string**  | PHP string  | ``^.+$``         |
| **boolean** | PHP boolean | ``^true|false$`` |
| **integer** | PHP integer | ``^\d+$``        |

## 4. How to write a custom parameter type

Your parameter type class must implement ``ParameterType\ParameterTypeInterface`` with 2 methods :

1. ``castValue($value)``
2. ``getDefaultRegExp()``

Then declare it as a service, and add the tag ``gl3n_http_query_string_filter.parameter_type`` with the property ``type``.

Here is an example of XML declaration :

```xml
<service
    id="gl3n_http_query_string_filter.parameter_type.boolean"
    class="Gl3n\HttpQueryStringFilterBundle\ParameterType\BooleanParameterType"
>
    <tag name="gl3n_http_query_string_filter.parameter_type" type="boolean"/>
</service>
```





