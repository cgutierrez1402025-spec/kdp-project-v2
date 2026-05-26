# Contribuir a KDP Author Manager

¡Gracias por tu interés en contribuir a KDP Author Manager!

## 📋 Cómo Contribuir

### 1. Fork el Repositorio
```bash
git clone https://github.com/tu-usuario/kdp_project.git
cd kdp_project
git checkout -b feature/mi-feature
```

### 2. Hacer Cambios
- Crea modelos siguiendo el patrón de `Work_model.php`
- Crea Resources siguiendo el patrón de `WorkResource.php`
- Agrega validaciones
- Crea tests

### 3. Commits Claros
```bash
git add .
git commit -m "type: descripción clara

- Detalle 1
- Detalle 2

Assisted-By: Gordon"
```

**Tipos de commit:**
- `feat:` Nueva característica
- `fix:` Corrección de bug
- `docs:` Cambios en documentación
- `refactor:` Mejoras de código
- `test:` Agregar o mejorar tests
- `chore:` Tareas de mantenimiento

### 4. Push y Pull Request
```bash
git push origin feature/mi-feature
```

Luego crea un Pull Request en GitHub con:
- Descripción clara de cambios
- Referencia a issues relacionados
- Tests incluidos
- Documentación actualizada

## 📝 Guía de Estilo

### PHP/Laravel
```php
// Use nombres descriptivos
class UserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}

// Type hints siempre
public function store(CreateWorkRequest $request): Work
{
    return Work::create($request->validated());
}

// Docstrings para métodos públicos
/**
 * Crear nueva obra
 * 
 * @param array $data Datos de la obra
 * @return Work Obra creada
 */
public function create(array $data): Work
```

### Nombres de Archivos
- Modelos: `Work.php`, `Publication.php` (singular, PascalCase)
- Resources: `WorkResource.php` (singular + Resource)
- Controllers: `WorkController.php` (singular + Controller)
- Migrations: `2025_01_01_000000_create_works_table.php`
- Seeders: `DemoDataSeeder.php`

## 🧪 Testing

Agregar tests para nuevas características:

```php
// tests/Unit/Models/WorkTest.php
class WorkTest extends TestCase
{
    /** @test */
    public function can_create_work()
    {
        $work = Work::factory()->create();
        $this->assertInstanceOf(Work::class, $work);
    }
}
```

Ejecutar tests:
```bash
php artisan test
php artisan test --coverage
```

## 📚 Documentación

Actualizar documentación con cambios:
- `README.md` - Cambios principales
- `IMPLEMENTATION_CHECKLIST.md` - Nuevas fases
- Comentarios en código - Lógica compleja
- Docstrings - Métodos públicos

## 🐛 Reportar Bugs

Crear un Issue con:
1. Descripción clara del bug
2. Pasos para reproducir
3. Comportamiento esperado
4. Capturas de pantalla (si aplica)
5. Versión de PHP/Laravel

## ✨ Sugerir Características

Crear un Issue con:
1. Descripción de la característica
2. Casos de uso
3. Ejemplo de cómo se vería
4. Impacto en la BD (si aplica)

## 📖 Áreas de Contribución

Necesitamos ayuda en:
- **Modelos** (crear 30+ modelos restantes)
- **Resources** (crear 15+ resources restantes)
- **Tests** (cobertura del 80%+)
- **Documentación** (guías de usuario, videos)
- **API REST** (crear endpoints)
- **Mobile App** (React Native)
- **Traducción** (i18n)
- **Performance** (optimización)
- **Seguridad** (audits, hardening)

## 🎯 Prioridades

1. **Modelos y Resources** - Core functionality
2. **Tests** - Estabilidad
3. **Documentación** - Usabilidad
4. **Optimización** - Performance
5. **Features Avanzadas** - Enhancement

## 📞 Comunicación

- Issues: Para bugs y features
- Discussions: Para preguntas y ideas
- Email: Para contacto privado

## 📜 Código de Conducta

- Sé respetuoso
- Sé inclusivo
- Sé constructivo
- Sé profesional

## ✅ Checklist Antes de Hacer PR

- [ ] Código sigue guía de estilo
- [ ] Tests pasan (`php artisan test`)
- [ ] Documentación actualizada
- [ ] Sin errores en IDE
- [ ] Commits son claros
- [ ] PR describe cambios

## 🙏 Agradecimientos

¡Gracias por contribuir a mejorar KDP Author Manager!

---

**Licencia:** MIT  
**Versión:** 1.0.0  
**Última actualización:** 2025
